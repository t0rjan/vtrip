//
//  mlViewElementTimeClock.m
//  trip
//
//  Created by 沈 吾苓 on 13-2-21.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import "mlViewElementTimeClock.h"
#import <QuartzCore/QuartzCore.h>

@implementation mlViewElementTimeClock
@synthesize isShowing;
@synthesize timeLb;


- (id)initWithFrame:(CGRect)frame
{
    CGRect f = CGRectMake(frame.origin.x, frame.origin.y, 30, 30);
    self = [super initWithFrame:f];
    if (self) {
        self.isShowing = NO;
        
        [self render];
    }
    return self;
}


- (void)render
{
    self.bg = [[UIView alloc] initWithFrame:CGRectMake(0, 0, 30, 30)];
    self.bg.backgroundColor = [UIColor blackColor];
    self.bg.layer.cornerRadius = 15;
    self.bg.alpha = 0.0;
    self.bg.layer.borderColor = [UIColor darkGrayColor];
    [self addSubview:self.bg];
    self.backgroundColor = [UIColor clearColor];
    
    UIButton *clockBtn = [UIButton buttonWithType:UIButtonTypeCustom];
    clockBtn.frame = CGRectMake(2, 2, 26, 26);
    self.backgroundColor = [UIColor clearColor];
    [clockBtn addTarget:self action:@selector(showTime) forControlEvents:UIControlEventTouchUpInside];
    UIImage *btnPic = [UIImage imageNamed:@"icon_clock.png"];
    [clockBtn setImage:btnPic forState:UIControlStateNormal];
    
    [self addSubview:clockBtn];
    
    self.timeLb = [[UILabel alloc] initWithFrame:CGRectMake(32, 2, 44, 26)];
    self.timeLb.backgroundColor = [UIColor clearColor];
    self.timeLb.text = @"12:34";
    self.timeLb.textColor = [UIColor whiteColor];
    self.timeLb.alpha = 0;
    self.timeLb.hidden = YES;
    
    [self addSubview:self.timeLb];
}

- (void)showTime
{

    [UIView animateWithDuration:0.3 animations:^{
        self.bg.frame = CGRectMake(0, 0, 90, 30);
        self.bg.alpha = 0.6;
    } completion:^(BOOL cc){
        
        self.timeLb.hidden = NO;
        [UIView animateWithDuration:0.3 animations:^{
            self.timeLb.alpha = 1;
        } completion:^(BOOL cc){
            self.isShowing = YES;
            [NSTimer scheduledTimerWithTimeInterval:1 target:self selector:@selector(hideTime) userInfo:nil repeats:nil];
        }];
        
    }];
}
- (void)hideTime
{
    [UIView animateWithDuration:0.3 animations:^{
        self.timeLb.alpha = 0;

    } completion:^(BOOL cc){
        self.timeLb.hidden = YES;
        
        [UIView animateWithDuration:0.3 animations:^{
            self.bg.frame = CGRectMake(0, 0, 30, 30);
            self.bg.alpha = 0.0;
        } completion:^(BOOL cc){
            self.isShowing = NO;
        }];
        
    }];

}
@end
