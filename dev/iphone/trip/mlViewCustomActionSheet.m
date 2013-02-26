//
//  mlViewCustomActionSheet.m
//  trip
//
//  Created by 沈 吾苓 on 13-2-16.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import "mlViewCustomActionSheet.h"

@implementation mlViewCustomActionSheet

@synthesize Layerheight;

- (id)initWithFrame:(CGRect)frame
{
    self = [super initWithFrame:frame];
    if (self) {
        [self setDuration:0.3];
    }
    return self;
}

- (id)initWithHeight:(CGFloat)height
{
    CGRect frame = CGRectMake(0, [toolIosSize heightWithoutStatusBar], [toolIosSize widthScreen], height);
    self = [super initWithFrame:frame];
    if (self) {
        self.Layerheight = height;
        self.backgroundColor = [UIColor darkGrayColor];
    }
    return self;
}

- (void)_hide
{
    self.transform = CGAffineTransformTranslate(self.transform, 0, self.Layerheight);
    
}
- (void)_show
{
    self.transform = CGAffineTransformTranslate(self.transform, 0, (CGFloat)(0-self.Layerheight));
    
    
}

@end
