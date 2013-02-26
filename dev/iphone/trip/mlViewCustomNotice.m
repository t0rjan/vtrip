//
//  mlViewCustomNotice.m
//  trip
//
//  Created by 沈 吾苓 on 13-2-18.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import "mlViewCustomNotice.h"

@implementation mlViewCustomNotice

@synthesize bg;

- (id)initWithFrame:(CGRect)frame
{

    if (frame.size.width == 0) {
        frame = CGRectMake(50, 80, 220, 220);
    }
    self = [super initWithFrame:frame];
    if (self) {
        self.frame = frame;
        self.backgroundColor = [UIColor colorWithRed:0 green:0 blue:0 alpha:0.7];
        self.layer.cornerRadius = 8;
        self.isShowing = false;
        self.hidden = YES;
//        
//        UIButton *close = [UIButton buttonWithType:UIButtonTypeCustom];
//        close.frame = CGRectMake(frame.size.width-20, 1, 19, 19);
//        close.backgroundColor = [UIColor redColor];
//        close.layer.cornerRadius = 9;
//        [close addTarget:self action:@selector(hideAll) forControlEvents:UIControlEventTouchUpInside];
//        [self addSubview:close];
//        
        [self setAlpha:0];
        [self setDuration:0.3];
    }
    return self;
}

- (void)_hide
{
    [self setAlpha:0];
    //CGAffineTransformTranslate(self.transform, 0, self.Layerheight);
    
}
- (void)_show
{
    [self setAlpha:1];
}

- (void)_show_begin{
    self.tapGesture = [[UITapGestureRecognizer alloc] initWithTarget:self action:@selector(hideAll)];
    [self.superview addGestureRecognizer:self.tapGesture];
    self.hidden = NO;
}
- (void)_hide_complete
{
    self.hidden = YES;
    [self.superview removeGestureRecognizer:self.tapGesture];
}
- (void)showInView:(UIView *)view forSecond:(int)sec
{
    [self showInView:view];
    NSTimer *timer = [NSTimer scheduledTimerWithTimeInterval:(NSTimeInterval)sec target:self selector:@selector(hideAll) userInfo:nil repeats:NO];
    
}
@end
