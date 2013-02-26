//
//  mlViewCustomLayer.m
//  trip
//
//  Created by 沈 吾苓 on 13-2-16.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import "mlViewCustomLayer.h"


static NSMutableDictionary *mlViewCustomLayers;
static NSInteger *mlViewCustomLayerIdShowing;


@implementation mlViewCustomLayer

@synthesize layerId;
@synthesize isShowing;
@synthesize duration;

- (id)initWithFrame:(CGRect)frame
{
    if (mlViewCustomLayers == NULL) {
        mlViewCustomLayers = [[NSMutableDictionary alloc] init];
        mlViewCustomLayerIdShowing = 0;
    }
    
    self = [super initWithFrame:frame];
    if (self) {
        self.isShowing = NO;
        [self _init_layerId];
        [mlViewCustomLayers setObject:self forKey:self.layerId];
    }
    duration = 0.3;

    return self;
}

- (void)showInView:(UIView *)view
{
    [view addSubview:self];
    [self show];
}

- (void)show
{
    if(mlViewCustomLayerIdShowing != self.layerId)
    {
        BOOL isToShow = NO;
        BOOL isToHide = NO;
        mlViewCustomLayer *showing = [mlViewCustomLayers objectForKey:mlViewCustomLayerIdShowing];
        if(mlViewCustomLayerIdShowing)
        {
            mlViewCustomLayerIdShowing = 0;
            showing.isShowing = NO;
            isToHide = YES;
            [showing _hide_begin];
        }
        if(!self.isShowing)
        {
            mlViewCustomLayerIdShowing = self.layerId;
            self.isShowing = YES;
            isToShow = YES;
            [self _show_begin];
        }

        [UIView animateWithDuration:self.duration animations:^{
            if(isToHide)
            {
                [showing _hide];
            }
            if(isToShow)
            {
                [self _show];
            }
        } completion:^(BOOL completion){
            if(isToHide)
            {
                [showing _hide_complete];
            }
            if (isToShow) {
                [self _show_complete];
            }
        }];
    }
}
- (void)hideAll
{
    if (mlViewCustomLayerIdShowing) {
        mlViewCustomLayer *showing = [mlViewCustomLayers objectForKey:mlViewCustomLayerIdShowing];
        [showing _hide_begin];
        [UIView animateWithDuration:self.duration animations:^{
                mlViewCustomLayerIdShowing = 0;
                showing.isShowing = NO;
                [showing _hide];
            
        } completion:^(BOOL completion){
            [showing _hide_complete];
            [self removeFromSuperview];

        }];
    }
}

- (void)_hide{}
- (void)_show{}
- (void)_show_begin{}
- (void)_hide_begin{}
- (void)_show_complete{}
- (void)_hide_complete{}

- (void)_init_layerId
{

    self.layerId = [NSString stringWithFormat:@"id%d" , [mlViewCustomLayers count]];

}
@end
